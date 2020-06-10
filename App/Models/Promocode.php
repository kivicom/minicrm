<?php

namespace App\Models;


use Aura\SqlQuery\QueryFactory;
use PDO;

class Promocode
{
    public $rand_gen;
    public $sum_promo;
    public $appeal_status;
    public $num_automat;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getOne($table, $rand_gen)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where("`rand_gen` = '{$rand_gen}'")
            ->limit(1);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        $appeal = $sth->fetch(PDO::FETCH_ASSOC);

        return $appeal;
    }

    public function getUrl($num_automat)
    {
        $url = '';
        $machines = file_get_contents('./protected/machines.txt');
        $lines = explode(PHP_EOL, $machines);

        foreach ($lines as $i => $line){
            $line = explode('*', $line);

            if($line[0] === $num_automat){
                $url = $line[1];
            }
        }

        return $url;
    }

    public function createFile($filename, $data, $usedDate)
    {
        $file = "./discount/".$filename.".txt";
        $string = implode('*', $data);
        //проверяем если такой файл
        if(!file_exists($file))
        {
            // откроет файл для чтения
            $handle = fopen($file, 'w');
            if (fwrite($handle, $string) === FALSE) {
                return false;
            }

            // закроет файл
            fclose($handle);

            if(file_exists($file)){
                $this->getUpdateStatus('appeal', $data, $usedDate, $filename);
                $this->getUsedPromo('appeal', $data, $usedDate);
            }

            return true;
        }
    }

    /*public function deleteFile($filename)
    {
        $filename = $filename['filename'].".txt";
        $directory ="./discount/";
        // открываем директорию (получаем дескриптор директории)
        $dir = opendir($directory);

        while(($file = readdir($dir)))
        {
            if(is_file("$directory/$filename") && ("$directory/$file" == "$directory/$filename")){

                // ...удаляем его.
                unlink("$directory/$filename");

                // Если файла нет по запрошенному пути, возвращаем TRUE - значит файл удалён.
                if(!file_exists($directory."/".$filename)){
                    return true;
                }
            }
        }
        closedir($dir);
    }

    public function getFileName($table, $id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['`filename`'])
            ->from($table)
            ->where("`id` = '{$id}'")
            ->limit(1);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        $filename = $sth->fetch(PDO::FETCH_ASSOC);

        return $filename;
    }*/

    public function getUpdateStatus($table,$data, $usedDate, $filename)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols([
                'appeal_status' => 'Использован',
                'used_promo' => $usedDate,
                'filename' => $filename,
                ])
            ->where('rand_gen = :rand_gen')
            ->bindValue('rand_gen', $data['rand_gen']);
        $sth = $this->pdo->prepare($update->getStatement());

        return $sth->execute($update->getBindValues());
    }

    public function getUsedPromo($table, $data, $usedDate)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols(['used_promo' => $usedDate])
            ->where('rand_gen = :rand_gen')
            ->bindValue('rand_gen', $data['rand_gen']);
        $sth = $this->pdo->prepare($update->getStatement());

        return $sth->execute($update->getBindValues());
    }
}