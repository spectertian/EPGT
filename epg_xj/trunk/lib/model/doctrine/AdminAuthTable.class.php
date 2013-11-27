<?php

class AdminAuthTable extends Doctrine_Table
{

    public function setAuths($admin_id, $credentials) {
        $this->delAllAuths($admin_id);
        foreach($credentials as $credential) {
            $auth = new AdminAuth();
            $auth->setAdminId($admin_id);
            $auth->setCredential($credential);
            $auth->save();
        }
    }

    /**
     * 删除用户所有证书
     * @param <int> $admin_id
     */
    public function delAllAuths($admin_id) {
        $credentials = Doctrine::getTable('AdminAuth')->createQuery()
                    ->where('admin_id = ?', $admin_id)
                    ->execute();
        foreach($credentials as $credential) {
            $credential->delete();
        }
    }
}