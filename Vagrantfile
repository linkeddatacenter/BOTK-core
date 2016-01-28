# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	config.vm.box = "opscode-ubuntu-14.04"
	config.vm.box_url = "https://opscode-vm-bento.s3.amazonaws.com/vagrant/virtualbox/opscode_ubuntu-14.04_chef-provisionerless.box"	
	#config.vm.box = "ubuntu/trusty64"
	config.vm.provision "shell", path: "test/_support/install.sh"
	config.vm.network "forwarded_port", guest: 80, host: 8080
end