<?php

namespace Clube\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Clube\SiteBundle\Entity\Video;
use Clube\SiteBundle\Form\VideoType;
use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

/**
 * Video controller.
 *
 * @Route("/video")
 */
class VideoController extends Controller
{

    /**
     * Lists all Video entities.
     *
     * @Route("/", name="video")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteBundle:Video')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Video entity.
     *
     * @Route("/", name="video_create")
     * @Method("POST")
     * @Template("SiteBundle:Video:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->get('security.context')->getToken()->getUser();
        $userProfile = $em->getRepository('SiteBundle:User')->find($usr->getId());

        $entity = new Video();
        $form = $this->createCreateForm($entity)->add('file');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setUser($userProfile);
            $entity->setCreateDate(new \DateTime("now"));

            $em->persist($entity);
            $em->flush();
            $this->_sendS3($entity);

            return $this->redirect($this->generateUrl('projetos_show', array('id' => $entity->getProject()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    private function _sendS3($video)
    {
        $bucket = $this->container->getParameter('aws_bucket');
        $pathToFile = $video->getAbsolutePath();
        $fileName = $video->getId() . '.' . $video->getPath();

        // Create an Amazon S3 client object
        $client = S3Client::factory(array(
            'key'    => $this->container->getParameter('aws_access_key'),
            'secret' => $this->container->getParameter('aws_secret_key')
        ));

        $result = $client->putObject(array(
            'Bucket'     => $bucket,
            'Key'        => $fileName,
            'SourceFile' => $pathToFile
        ));

        $this->_transcoding($video);
    }

    private function _transcoding($video)
    {
        $client = ElasticTranscoderClient::factory(array(
            'key'    => $this->container->getParameter('aws_access_key'),
            'secret' => $this->container->getParameter('aws_secret_key'),
            'region' => 'us-west-2'
        ));

        $pipelineId = $this->container->getParameter('aws_pipeline_id');
        $inputKey = $video->getId() . '.' . $video->getPath();
        $outputPrefix360 = 'cc360/';
        $outputPrefix720 = 'cc720/';

        $client->createJob(array(
            'PipelineId' => $pipelineId,
            'Input' => array(
                'Key' => $inputKey,
            ),
            'ThumbnailPattern' => 't-{resolution}',
            'Thumbnails' => array(
                'Format' => 'jpg',
                'Interval' => '3',
                'AspectRatio' => 'auto',
            ),
            'Output' => array(
                'Key' => $inputKey,
                'PresetId' => '1351620000001-000040'
            ),
            'OutputKeyPrefix' => $outputPrefix360,
        ));

        $client->createJob(array(
            'PipelineId' => $pipelineId,
            'Input' => array(
                'Key' => $inputKey,
            ),
            'ThumbnailPattern' => 't-{resolution}',
            'Output' => array(
                'Key' => $inputKey,
                'PresetId' => '1351620000001-000010'
            ),
            'OutputKeyPrefix' => $outputPrefix720,
            'Thumbnails' => array(
                'Format' => 'png',
                'Interval' => '3',
                'AspectRatio' => 'auto',
            ),
        ));
    }

    private function _sendYoutube($videoFile, $videoTitle)
    {
        // Call set_include_path() as needed to point to your client library.
        require_once 'Google/Client.php';
        require_once 'Google/Service/YouTube.php';
        //session_start();

        /*
         * You can acquire an OAuth 2.0 client ID and client secret from the
         * Google Developers Console <https://cloud.google.com/console>
         * For more information about using OAuth 2.0 to access Google APIs, please see:
         * <https://developers.google.com/youtube/v3/guides/authentication>
         * Please ensure that you have enabled the YouTube Data API for your project.
         */
        $OAUTH2_CLIENT_ID = $this->container->getParameter('google_app_id');
        $OAUTH2_CLIENT_SECRET = $this->container->getParameter('google_app_secret');

        $client = new \Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
            FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);

// Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($client);

        if (isset($_GET['code'])) {
            if (strval($_SESSION['state']) !== strval($_GET['state'])) {
                die('The session state did not match.');
            }

            $client->authenticate($_GET['code']);
            $_SESSION['token'] = $client->getAccessToken();
            header('Location: ' . $redirect);
        }

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }

// Check to ensure that the access token was successfully acquired.
        if ($client->getAccessToken()) {
            try{
                // REPLACE this value with the path to the file you are uploading.
                $videoPath = $videoFile;

                // Create a snippet with title, description, tags and category ID
                // Create an asset resource and set its snippet metadata and type.
                // This example sets the video's title, description, keyword tags, and
                // video category.
                $snippet = new \Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle($videoTitle);
                //$snippet->setDescription("");
                //$snippet->setTags(array("tag1", "tag2"));

                // Numeric video category. See
                // https://developers.google.com/youtube/v3/docs/videoCategories/list
                $snippet->setCategoryId("22");

                // Set the video's status to "public". Valid statuses are "public",
                // "private" and "unlisted".
                $status = new \Google_Service_YouTube_VideoStatus();
                $status->privacyStatus = "unlisted";

                // Associate the snippet and status objects with a new video resource.
                $video = new \Google_Service_YouTube_Video();
                $video->setSnippet($snippet);
                $video->setStatus($status);

                // Specify the size of each chunk of data, in bytes. Set a higher value for
                // reliable connection as fewer chunks lead to faster uploads. Set a lower
                // value for better recovery on less reliable connections.
                $chunkSizeBytes = 1 * 1024 * 1024;

                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);

                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtube->videos->insert("status,snippet", $video);

                // Create a MediaFileUpload object for resumable uploads.
                $media = new \Google_Http_MediaFileUpload(
                    $client,
                    $insertRequest,
                    'video/*',
                    null,
                    true,
                    $chunkSizeBytes
                );
                $media->setFileSize(filesize($videoPath));


                // Read the media file and upload it chunk by chunk.
                $status = false;
                $handle = fopen($videoPath, "rb");
                while (!$status && !feof($handle)) {
                    $chunk = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }

                fclose($handle);

                // If you want to make other calls after the file upload, set setDefer back to false
                $client->setDefer(false);

                return $status['id'];

            } catch (Google_ServiceException $e) {
                //$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                //    htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                //$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                //    htmlspecialchars($e->getMessage()));
            }

            $_SESSION['token'] = $client->getAccessToken();
        } else {
            // If the user hasn't authorized the app, initiate the OAuth flow
            $state = mt_rand();
            $client->setState($state);
            $_SESSION['state'] = $state;

            $authUrl = $client->createAuthUrl();
            //$htmlBody = <<<END
            //<h3>Authorization Required</h3>
            //<p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
            //END;
        }

    }

    /**
    * Creates a form to create a Video entity.
    *
    * @param Video $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Video $entity)
    {
        $form = $this->createForm(new VideoType(), $entity, array(
            'action' => $this->generateUrl('video_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Video entity.
     *
     * @Route("/new", name="video_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('SiteBundle:Project')->find($id);

        if ($project == null)
            throw new NotFoundHttpException("Page not found");

        $maxVideos = $project->getMaxVideos();

        $usr = $this->get('security.context')->getToken()->getUser();
        $total = $em->getRepository('SiteBundle:User')->find($usr->getId())->getVideos()->count();

        $maxVideos = $maxVideos - $total;

        $entity = new Video();
        $entity->setProject($project);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'maxVideos' => $maxVideos
        );
    }

    /**
     * Finds and displays a Video entity.
     *
     * @Route("/{id}", name="video_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Video entity.
     *
     * @Route("/{id}/edit", name="video_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Video entity.
    *
    * @param Video $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Video $entity)
    {
        $form = $this->createForm(new VideoType(), $entity, array(
            'action' => $this->generateUrl('video_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Video entity.
     *
     * @Route("/{id}", name="video_update")
     * @Method("PUT")
     * @Template("SiteBundle:Video:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('video_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Video entity.
     *
     * @Route("/{id}", name="video_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteBundle:Video')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Video entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('video'));
    }

    /**
     * Creates a form to delete a Video entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('video_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
