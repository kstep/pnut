#!/usr/bin/perl

use strict;
use DBI;

my $dbh = DBI->connect("DBI:mysql:host=localhost;database=nastya", "root", "vecrekhen");

my $tname = $ARGV[0];
my $table = $dbh->selectall_arrayref("SHOW FIELDS FROM $tname", { Slice => {} });
my $ind = " " x 4;
my $phs = "<";
my $phe = ">";

my $cname = ucfirst(lc($tname));
$cname =~ s/s$//;

my $file  = $cname.".php";

my $class  = "\nclass Model_${phs}$cname${phe} extends Model_Db\n{\n\n${ind}protected \$_table = '$tname';\n";
my $attrs  = "\n${ind}protected \$_attributes = array(\n";
my $fields = "\n${ind}protected \$_fields = array(\n";

my $mlfield = 0;
my $mlattr  = 0;

my $getters = "";
my @getters;

foreach my $field (@$table)
{
	if ($field->{'Key'} =~ /PRI/)
	{
		$class .= "${ind}protected \$_pk = '".($field->{'Field'})."';\n";
		next;
	}

	my $fname = $field->{'Field'};
	my $ftype = $field->{'Type'};
	if ($ftype =~ /int/) { $ftype = "Model::TYPE_INTEGER"; }
	elsif ($ftype =~ /char|text|blob/) { $ftype = "Model::TYPE_STRING"; }
	elsif ($ftype =~ /enum/) { $ftype = "Model::TYPE_ENUM"; }
	elsif ($ftype =~ /set/) { $ftype = "Model::TYPE_SET"; }
	elsif ($ftype =~ /timestamp|date|time/) { $ftype = "Model::TYPE_TIMESTAMP"; }
	elsif ($ftype =~ /float|decimal/) { $ftype = "Model::TYPE_FLOAT"; }

	my $aname = $fname;
	if ($ftype eq "Model::TYPE_TIMESTAMP") { $aname =~ s/_at$//; }
	elsif ($ftype eq "Model::TYPE_INTEGER") { push @getters, $aname if $aname =~ s/_id$//; }
		
	$fields .= "${ind}${ind}'$fname' => $ftype,\n";
	$attrs  .= "${ind}${ind}'$aname' => '$fname',\n";

	$mlfield = length($fname) if (length($fname) > $mlfield);
	$mlattr  = length($aname) if (length($aname) > $mlattr);
}

$attrs  =~ s/'(.*?)' =>/"'$1'".(" " x ($mlattr - length($1))).' =>'/eg;
$fields =~ s/'(.*?)' =>/"'$1'".(" " x ($mlfield - length($1))).' =>'/eg;

$attrs  .= "${ind});\n";
$fields .= "${ind});\n";

foreach my $aname (@getters)
{
	my $gname = ucfirst(lc($aname));
	my $rname = ucfirst(lc($aname));

	$gname =~ s/_(.)/\U\1\E/g;
	$rname =~ s/_(.)/\U\1\E/g;

	$getters .= "\n${ind}public function get$gname()\n${ind}{\n${ind}${ind}return \$this->$aname? new Model_${phs}$rname${phe}(\$this->_db, \$this->$aname): null;\n${ind}}\n";
}

$class .= $attrs . $fields . $getters . "\n}\n";

if (!-f $file)
{
	open FOUT, ">", $file;
	print FOUT "<?php\n$class\n?>";
	close FOUT;
}
print "<?php\n$class\n?>";

