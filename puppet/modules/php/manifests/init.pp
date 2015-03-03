class php {

  package { 'php5-fpm':
    ensure => present,
    require => Exec["apt-get update"],
  }

  package { 'php5-mongo':
    ensure => present,
    require => Exec["apt-get update"],
  }

  package { 'phpunit':
    ensure => present,
    require => Exec["apt-get update"],
  }

  service { 'php5-fpm':
    ensure => running,
    require => Package["php5-fpm"],
    restart => "/usr/sbin/service php5-fpm reload",
  }
	
}