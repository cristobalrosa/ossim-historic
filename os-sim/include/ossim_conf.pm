package ossim_conf;
use strict;
use DBI;

BEGIN {

    local %ossim_conf::ossim_data;
    
    #
    # Read config from /etc/ossim.conf
    #
    open FILE, "/etc/ossim/framework/ossim.conf" 
        or die "Can't open logfile:  $!";

    while ($_ = <FILE>) {
        if(!(/^#/)) {
            if(/^(.*)=(.*)$/) {
                $ossim_conf::ossim_data->{$1} = $2;
            }
        }
    }

    close(FILE);

    #
    # Read config from database
    #
    my $ossim_type = $ossim_conf::ossim_data->{"ossim_type"};
    if ($ossim_type =~ /^postgres|^pg/i) {
        $ossim_type = "Pg";
    }

    my $dsn = "dbi:$ossim_type:" .
        "dbname=" . $ossim_conf::ossim_data->{"ossim_base"} . ";" .
        "host="   . $ossim_conf::ossim_data->{"ossim_host"} . ";" .
        "port="   . $ossim_conf::ossim_data->{"ossim_port"};

    my $conn = DBI->connect($dsn, 
                            $ossim_conf::ossim_data->{"ossim_user"}, 
                            $ossim_conf::ossim_data->{"ossim_pass"}) 
        or die "Can't connect to Database\n";

    my $query = "SELECT * FROM config";
    my $stm = $conn->prepare($query);
    $stm->execute();
    
    while (my $row = $stm->fetchrow_hashref) {
        if (!$ossim_conf::ossim_data->{$row->{"conf"}}) {
            $ossim_conf::ossim_data->{$row->{"conf"}} = $row->{"value"};
        }
    }
    $stm->finish();
    $conn->disconnect();

}
1;

