FROM busybox

VOLUME [ \
    "/var/lib/neo4j/data", \
    "/var/www/html/var/cache", \
    "/var/www/html/logs" \
]

ENTRYPOINT chmod -R 777 \
    /var/lib/neo4j/data \
    /var/www/html/var/cache \
    /var/www/html/logs
