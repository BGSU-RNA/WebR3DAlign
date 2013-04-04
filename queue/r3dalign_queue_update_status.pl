#!/usr/bin/perl

use strict;
use warnings;
use FindBin qw($RealBin);

use DBI;

if ($#ARGV != 0 ) {
	print "Provide query_id\n";
	exit;
}

sleep(1);

my %config = do $RealBin . '/r3dalign_queue_config.pl';
my $dsn = 'DBI:mysql:' . $config{db_database}. ':localhost';
my $dbh = DBI->connect($dsn, $config{db_user_name}, $config{db_password});

my $input = $ARGV[0];

my $result_file = $config{results_dir} . "/$input/$input.pdb";
my $error_file  = $config{results_dir} . "/$input/$input" . "_error.txt";

my $status = 0;
# check for errors first in case only the first iteration(s) were successful
if (-e $error_file) {
    $status = -2; # found matlab error report
} elsif (-e $result_file) {
    $status = 1; # found results
} else {
    $status = -1; # no output files, the job timed out
}

my $statement = "UPDATE `query` SET `status` = $status, `time_completed` = NOW(), `time_submitted` = `time_submitted` WHERE `query_id` = '$input';";
$dbh->do($statement);

$dbh->disconnect;
