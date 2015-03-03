include nginx
include mongo
include php

exec { "apt-get update":
    command => "/usr/bin/apt-get update",
}

file{"/etc/nginx/sites-available/genghis":
  ensure => present,
  source => "puppet:///modules/core/genghis.conf",
  require => Package[nginx],
  before => Service[nginx],
}

file{"/etc/nginx/sites-available/mongolinks":
  ensure => present,
  source => "puppet:///modules/core/mongolinks.conf",
  require => Package[nginx],
  before => Service[nginx],
}

file{"/etc/nginx/sites-enabled/genghis":
  ensure => 'link',
  target => '/etc/nginx/sites-available/genghis',
  require => File["/etc/nginx/sites-available/genghis"],
  before => Service[nginx],
}

file{"/etc/nginx/sites-enabled/mongolinks":
  ensure => 'link',
  target => '/etc/nginx/sites-available/mongolinks',
  require => File["/etc/nginx/sites-available/mongolinks"],
  before => Service[nginx],
}

exec { "restart_nginx":
  command => "/usr/sbin/service nginx restart",
  require => File["/etc/nginx/sites-available/mongolinks"],
}

File["/etc/nginx/sites-available/genghis"] ->
File["/etc/nginx/sites-enabled/genghis"] ->
Exec["restart_nginx"]
