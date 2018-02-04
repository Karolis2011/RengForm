# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.network "forwarded_port", guest: 80, host: 8000
  config.vm.synced_folder "./", "/srv", type: "nfs"
  config.bindfs.bind_folder "/srv" , "/srv"
  config.vm.network :private_network, ip: "11.15.10.23"
  config.vm.hostname = "rengform2.test"

  config.vm.provider :virtualbox do |v|
      v.customize [
          "modifyvm", :id,
          "--memory", 2048,
          "--cpus", 2,
          "--name", "rengform"
      ]
      v.customize [
         "setextradata", :id,
         "VBoxInternal2/SharedFoldersEnableSymlinksCreate/vagrant", "1"
      ]
  end

  config.vm.provision :shell, path: "bootstrap.sh"
end

