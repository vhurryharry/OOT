lock "~> 3.11.2"

set :application, "school"
set :repo_url, "git@bitbucket.org:janestreetmedia/school.git"
set :linked_files, []
set :file_permissions_paths, -> { [fetch(:var_path), fetch(:cache_path)] }
set :permission_method, :acl
set :file_permissions_users, ["www-data", "deploy"]
set :php_fpm_service, 'php7.3-fpm'

set :bin_path, "backend/bin"
set :symfony_console_path, "backend/bin/console"

set :composer_working_dir, -> { "#{fetch(:release_path)}/backend" }

namespace :app do
	namespace :backend do
		desc 'Restart php-fpm'
		task :restart do
			on roles(:all) do
				within "#{release_path}/backend" do
					execute :sudo, "service #{fetch(:php_fpm_service)} restart"
				end
			end
		end

		desc 'Setup backend ACL'
		task :acl do
			on roles(:app) do
				within "#{release_path}/backend" do
					execute :setfacl, '-R -m u:www-data:rwX -m u:deploy:rwX var/cache'
					execute :setfacl, '-dR -m u:www-data:rwx -m u:deploy:rwx var/cache'
				end
			end
		end
	end

	namespace :frontend do
		desc 'Setup frontend assets folder'
		task :setup do
			on roles(:app) do
				within "#{release_path}/backend" do
					execute :mkdir, '-p public/assets'
					execute :setfacl, '-R -m u:www-data:rwX -m u:deploy:rwX public/assets'
					execute :setfacl, '-dR -m u:www-data:rwx -m u:deploy:rwx public/assets'
				end
			end
		end

		desc 'Build frontend artifacts'
		task :build do
			on roles(:app) do
				within "#{release_path}/frontend" do
					execute :npm, 'ci --silent --no-progress --no-color'
					execute :npm, 'run build --prod'
				end

				within "#{release_path}/admin" do
					execute :npm, 'ci --silent --no-progress --no-color'
					execute :npm, 'run build --prod'
				end
			end
		end

		desc 'Run admin panel'
		task :run do
			on roles(:app) do
				within "#{release_path}/admin" do
					execute :pm2, 'stop oot-admin'
					execute :pm2, 'start "ng serve" --name oot-admin'
				end
			end
		end
	end
end

#before 'symfony:cache:warmup', 'app:backend:acl'
before 'symfony:cache:warmup', 'app:frontend:setup'
after 'app:frontend:setup', 'app:frontend:build'
after 'deploy:published', 'app:backend:restart'
after 'app:backend:restart', 'app:frontend:run'
