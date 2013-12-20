set :application, "clubedoscriadores"
set :domain,      "www.clubedoscriadores.com.br"
set :deploy_to,   "/home/clubecriadores"

set :app_path,    "app"


set :scm,         :none
set :repository, "."
set :deploy_via, :copy

set :ssh_options, {:forward_agent => true}
set :use_sudo, false
set :user, "ec2-user"

ssh_options[:auth_methods] = ["publickey"]
ssh_options[:keys] = ["/projetos/td-linux01.pem"]
ssh_options[:paranoid] = false
default_run_options[:pty] = true
set :copy_exclude, [".git", "spec"]

role  :web,           domain
role  :app,           domain
role  :db,            domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3

set :shared_files,      ["app/config/parameters.ini"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors, true

set :admin_runner, "ec2-user"
namespace :deploy do
  task :setup, :except => { :no_release => true } do
    dirs = [deploy_to, releases_path, shared_path]
    dirs += shared_children.map { |d| File.join(shared_path, d.split('/').last) }
    run "mkdir -p #{dirs.join(' ')}"
  end
end