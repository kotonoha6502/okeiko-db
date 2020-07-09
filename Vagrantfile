# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "bento/centos-8"
  config.vm.box_check_update = true

  config.vm.hostname = "okeikodblocaldev"
  config.vm.network "private_network", ip: "192.168.33.51"
  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--memory", "1024"]
  end

  config.vm.synced_folder ".", "/vagrant", disabled: true
  config.vm.synced_folder "ansible", "/ansible", type: "virtualbox"
  config.vm.synced_folder "backend", "/opt/okeiko", type: "nfs"

  config.vm.provision "shell", inline: "dnf update -y && dnf install -y python3 && pip3 install --upgrade pip ansible"
  config.vm.provision "ansible_local" do |ansible|
    ansible.provisioning_path = "/ansible"
    ansible.extra_vars = { ansible_python_interpreter:"/usr/bin/python3" }
    ansible.playbook = "site.yml"
    ansible.inventory_path = "inventories/localdev"
    ansible.galaxy_role_file = "requirements.yml"
    ansible.galaxy_command = "ansible-galaxy install --role-file=%{role_file} --roles-path=%{roles_path}"
    ansible.verbose = "vv"
    ansible.limit = "all"
  end
end
