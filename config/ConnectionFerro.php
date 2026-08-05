<?php

class ConnectionFerro{
    private $driver = 'pgsql';
    private $host;
    private $dbname;
    private $port;
    private $user;
    private $password;
    private $connect;

    public function __construct(){
        $databaseUrl = getenv('DATABASE_URL');

        if($databaseUrl){
            // Formato que provee Railway (y otras nubes): postgresql://usuario:clave@host:puerto/dbname
            $partes = parse_url($databaseUrl);
            $this->host = $partes['host'];
            $this->port = $partes['port'] ?? '5432';
            $this->user = rawurldecode($partes['user'] ?? 'postgres');
            $this->password = rawurldecode($partes['pass'] ?? '');
            $this->dbname = ltrim($partes['path'] ?? '', '/');
        }
        else{
            // Variables sueltas (o valores por defecto para desarrollo local con XAMPP)
            $this->host = getenv('PGHOST') ?: 'localhost';
            $this->port = getenv('PGPORT') ?: '5432';
            $this->user = getenv('PGUSER') ?: 'postgres';
            $this->password = getenv('PGPASSWORD') ?: '1403';
            $this->dbname = getenv('PGDATABASE') ?: 'postgres';
        }
    }

    static public function getConnection(){
        try{
            $connection = new ConnectionFerro();
            $connection->connect =
                new PDO("{$connection->driver}:host={$connection->host};
                    port={$connection->port};
                    dbname={$connection->dbname}",
                    $connection->user,
                    $connection->password
                );
            $connection->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $connection->connect->exec(
                "SET search_path TO geriatrico"
            );

            // echo "Conectado a la DB";
            return $connection->connect;
        } catch (PDOException $e) {
            echo "Error: ". $e->getMessage();
        }
    }
}

// Connection::getConnection();


?>