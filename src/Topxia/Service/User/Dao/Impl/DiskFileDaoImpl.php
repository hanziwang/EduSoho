<?php

namespace Topxia\Service\User\Dao\Impl;

use Topxia\Service\Common\BaseDao;
use Topxia\Service\User\Dao\DiskFileDao;
    
class DiskFileDaoImpl extends BaseDao implements DiskFileDao
{
    protected $table = 'user_disk_file';

    public function getFile($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        return $this->getConnection()->fetchAssoc($sql, array($id)) ? : null;
    }

    public function searchFiles($conditions, $orderBy, $start, $limit)
    {
        $builder = $this->_createQueryBuilder($conditions)
            ->select('*')
            ->orderBy($orderBy[0], $orderBy[1])
            ->setFirstResult($start)
            ->setMaxResults($limit);
        return $builder->execute()->fetchAll() ? : array(); 
    }

    public function searchFileCount($conditions)
    {
        $builder = $this->_createQueryBuilder($conditions)
            ->select('COUNT(id)');
        return $builder->execute()->fetchColumn(0);
    }

    public function addFile(array $file)
    {
        $affected = $this->getConnection()->insert($this->table, $file);
        if ($affected <= 0) {
            throw $this->createDaoException('Insert user disk file error.');
        }
        return $this->getFile($this->getConnection()->lastInsertId());
    }

    private function _createQueryBuilder($conditions)
    {
        return $this->createDynamicQueryBuilder($conditions)
            ->from($this->table, $this->table)
            ->andWhere('userId = :userId')
            ->andWhere('type = :type');
    }

}