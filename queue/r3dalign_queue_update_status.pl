#!/usr/bin/perl

use strict;
use warnings;
use FindBin qw($RealBin);

use DBI;

if ($#ARGV != 0 ) {
	print "Provide query_id\n";
	exit;
}

sleep(3);

my %config = do $RealBin . '/r3dalign_queue_config.pl';
my $dsn = 'DBI:mysql:' . $config{db_database}. ':localhost';
my $dbh = DBI->connect($dsn, $config{db_user_name}, $config{db_password});

my $input = $ARGV[0];

my $statement = "UPDATE `query` SET `status` = 1 WHERE `query_id` = '$input';";
$dbh->do($statement);

$dbh->disconnect;