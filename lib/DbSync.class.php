<?php
/**
 * Created by JetBrains PhpStorm.
 * User: asad-rahman
 * Date: 5/31/13
 * Time: 12:18 AM
 * To change this template use File | Settings | File Templates.
 */
Class DBSync{

    static function getDbDifferences(DbConf $source , DbConf $destination){

        $info = array();

        $sourceInfo      = $source->analyzeDb();
        $destinationInfo = $destination->analyzeDb();

        $sourceTables      = array_keys($sourceInfo);
        $destinationTables = array_keys($destinationInfo);

        $info['missing_tables'] = array_diff($sourceTables,       $destinationTables);
        $info['extra_tables'  ] = array_diff($destinationTables,  $sourceTables);

        if(empty($info['missing_tables'])) unset($info['missing_tables']);
        if(empty($info['extra_tables'])) unset($info['extra_tables']);

        $commonTables = array_intersect($sourceTables, $destinationTables);

        foreach($commonTables as $tableName ){

            $sourceColumns      = array_keys($sourceInfo[$tableName]);
            $destinationColumns = array_keys($destinationInfo[$tableName]);

            $info[$tableName] = array(
                'missing_columns' => array_diff($sourceColumns, $destinationColumns),
                'extra_columns'   => array_diff($destinationColumns, $sourceColumns),
            );

            if(empty($info[$tableName]['missing_columns'])){
                unset($info[$tableName]['missing_columns']);
            }

            if(empty($info[$tableName]['extra_columns'])){
                unset($info[$tableName]['extra_columns']);
            }

            $commonColumns = array_intersect($sourceColumns, $destinationColumns);

            foreach($commonColumns as $column){

                $srCol = $sourceInfo[$tableName][$column];
                $dsCol = $destinationInfo[$tableName][$column];

                foreach($srCol as $key => $val){

                    if($dsCol->$key === $val){
                        continue;
                    }

                    if(!isset($info[$tableName][$column])){
                        $info[$tableName][$column] = array();
                    }

                    $info[$tableName][$column][$key]= array(
                        'source'      => $val,
                        'destination' => $dsCol->$key,
                    );
                }
            }
        }

        foreach($info as $key => $item){
            if(empty($item)) unset($info[$key]);
        }

        return $info;
    }
}