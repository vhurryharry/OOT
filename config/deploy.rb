lock "~> 3.12.0"

set :application, "school"
set :repo_url, "git@bitbucket.org:janestreetmedia/school.git"
set :linked_files, []
set :file_permissions_paths, -> { [fetch(:var_path), fetch(:cache_path)] }
set :permission_method, :acl
set :file_permissions_users, ["www-data", "deploy"]

set :php_fpm_service, 'php7.3-fpm'

set :bin_path, "backend/bin"
set :config_path, "backend/config"
set :var_path, "backend/var"
set :web_path, "backend/public"

set :composer_working_dir, -> { "#{fetch(:release_path)}/backend" }

namespace :app do
	namespace :backend do
		desc 'Restart nginx'
		task :restart do
			on roles(:all) do
				within "#{release_path}" do
					execute :sudo, "service #{fetch(:php_fpm_service)} restart"
					execute :sudo, "service nginx restart"
					execute :sudo, "pm2 kill"
				end
			end
		end

		desc 'Setup backend ACL'
		task :acl do
			on roles(:app) do
                within "#{release_path}/backend" do
                    execute :chmod, '-R 777 public'
					execute :setfacl, '-R -m u:www-data:rwX -m u:deploy:rwX var/cache'
					execute :setfacl, '-dR -m u:www-data:rwx -m u:deploy:rwx var/cache'
					execute :setfacl, '-R -m u:www-data:rwX -m u:deploy:rwX var/log'
					execute :setfacl, '-dR -m u:www-data:rwx -m u:deploy:rwx var/log'
				end
			end
		end
	end

	namespace :admin do
		desc 'Run Admin'
		task :run do
			on roles(:app) do
				within "#{release_path}/admin" do
					execute :npm, 'install --silent --no-progress --no-color'
					execute :sudo, "pm2 start 'npm start' --name oot-admin"
				end
			end
		end
	end

	namespace :customer do
		desc 'Run Customer'
		task :run do
			on roles(:app) do
				within "#{release_path}/customer" do
					execute :npm, 'install --silent --no-progress --no-color'
					execute :sudo, "pm2 start 'npm start' --name oot-customer"
				end
			end
		end
	end
end

after 'deploy:published', 'app:backend:restart'
after 'app:backend:restart', 'app:admin:run'
after 'app:admin:run', 'app:customer:run'