<?php

namespace Unika\Scrapper;

use Illuminate\Database\Capsule\Manager as Capsule;

class ProccessManager
{
	protected $db;

	public function __construct(Capsule $db)
	{
		$this->db = $db;
	}

	public function put(ProccessJob $job)
	{

		$job->setDB($this->db);
		
		$this->db->table('src_logs')->insert([
			'url'		=>	$job->getUrl(),
			'city'		=>	$job->city,
			'keyword'	=>	$job->keyword,
			'source'	=>	$job->source,
			'limit'		=>	$job->limit,
			'category'	=>  $job->category,
			'created_at'	=>	date('Y-m-d H:i:s')
		]);
	}

	public function markAsStarted($jobId)
	{
		$this->db->table('src_logs')
			->where('id',$jobId)
			->update(['status' => 1]);
	}

	public function markAsDone($jobId,$records)
	{
		$this->db->table('src_logs')
			->where('id',$jobId)
			->update(['status' => 2,'records' => $records]);	
	}

	public function markAsFailed($jobId)
	{
		$this->db->table('src_logs')
			->where('id',$jobId)
			->update(['status' => 3]);	
	}

	public function fetch()
	{
		$rawJob = $this->db->table('src_logs')
			->where('status',0)
			->orderBy('id','ASC')
			->first();

		if( $rawJob ){
			$job = new ProccessJob($rawJob->limit,$rawJob->city,$rawJob->keyword,$rawJob->category);
			$job->setDB($this->db);
			$job->setId($rawJob->id);
			return $job;
		}

		return false;
	}
}