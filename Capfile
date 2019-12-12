require 'capistrano/setup'
require 'capistrano/deploy'
require 'capistrano/symfony'
require 'capistrano/scm/git'
install_plugin Capistrano::SCM::Git
Dir.glob('deploy/tasks/*.rake').each { |r| import r }
