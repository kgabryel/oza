<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ObjectManager;

class CronJobs extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jobs = [
            [
                'name' => 'deleteQuickLists',
                'command' => 'deleteQuickLists',
                'schedule' => '0 0 * * *',
                'description' => ''
            ],
            [
                'name' => 'deleteShoppingLists',
                'command' => 'deleteShoppingLists',
                'schedule' => '0 0 * * *',
                'description' => ''
            ]
        ];
        foreach ($jobs as $job) {
            $this->create($job, $manager);
        }
    }

    private function create(array $data, ObjectManager $manager): void
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('cnt', 'cnt');
        $query = $manager->createNativeQuery('SELECT count(*) AS cnt FROM cron_job where command = ?', $rsm);
        $query->setParameter(1, $data['command']);
        $result = $query->getOneOrNullResult();
        if ($result['cnt'] !== 0) {
            return;
        }
        $query = "
            INSERT INTO cron_job (id, name, command, schedule, description, enabled)
            values(nextval('cron_job_id_seq'), :name, :command, :schedule, :description, true)
            ";
        $manager->getConnection()->executeQuery($query, $data);
    }
}
