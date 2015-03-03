class mongo {

  package { 'mongodb-server':
    ensure => present,
    require => Exec["apt-get update"],
  }

  service { 'mongodb':
    ensure => running,
    require => Package[mongodb-server],
    restart => "/usr/sbin/service mongodb reload",
  }
	
}
