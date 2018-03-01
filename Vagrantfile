# Install required vagrant plugin(s)
required_plugins = %w(vagrant-bindfs)

plugins_to_install = required_plugins.select { |plugin| not Vagrant.has_plugin? plugin }
if not plugins_to_install.empty?
  puts "Installing plugins: #{plugins_to_install.join(' ')}"
  if system "vagrant plugin install #{plugins_to_install.join(' ')}"
    exec "vagrant #{ARGV.join(' ')}"
  else
    abort "Installation of one or more plugins has failed. Aborting."
  end
end

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.network "forwarded_port", guest: 80, host: 8000
  config.vm.synced_folder "./", "/srv", :nfs => true, :mount_options => ['actimeo=2']
  config.bindfs.bind_folder "/srv", "/srv"
  config.vm.network :private_network, ip: "11.15.10.23"
  config.vm.hostname = "rengform.test"

  config.vm.provider :virtualbox do |v|
      v.customize [
          "modifyvm", :id,
          "--memory", 2048,
          "--cpus", 2,
          "--name", "rengform",
          "--natdnshostresolver1", "on",
          "--nestedpaging", "on",
          "--largepages", "on"
      ]
  end

  config.vm.provision :shell, path: "bootstrap.sh"
end

