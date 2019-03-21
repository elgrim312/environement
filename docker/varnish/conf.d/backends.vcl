
backend server_nginx_0 {
    .host = "nginx";
    .port = "80";
    .probe = {
        .url = "/";
        .timeout = 1s;
        .interval = 60s;
        .window = 3;
        .threshold = 2;
    }
}

import std;
import directors;
import saintmode;
sub vcl_init {

  new cluster_nginx = directors.round_robin();

  cluster_nginx.add_backend(server_nginx_0);
}
acl purge {
    "localhost";
    "172.17.0.0/16";
    "10.42.0.0/16";
}

sub vcl_recv {
  if (req.method == "PURGE") {
    if (!client.ip ~ purge) {
        return(synth(405, "Not allowed."));
    }
    return (purge);
  }

  set req.backend_hint = cluster_nginx.backend();
}
