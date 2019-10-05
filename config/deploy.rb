lock "~> 3.11.2"

set :application, "school"
set :repo_url, "git@bitbucket.org:janestreetmedia/school.git"
set :linked_files, []
set :file_permissions_paths, -> { [fetch(:var_path), fetch(:cache_path)] }
set :ssh_options, { :forward_agent => true }
