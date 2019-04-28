<?php
use KanbanBoard\Authentication;
use KanbanBoard\GithubActual;
use KanbanBoard\Utilities;

require '../../vendor/autoload.php';
require '../classes/KanbanBoard/Github.php';
require '../classes/Utilities.php';
require '../classes/KanbanBoard/Authentication.php';

$dotenv = new Dotenv\Dotenv(__DIR__.'/../../');
$dotenv->load();

$repositories = explode('|', Utilities::env('GH_REPOSITORIES'));
$authentication = new \KanbanBoard\Login();
$token = $authentication->login();

//echo "This is token: ".$token; exit();

$client = new \Github\Client();
$repositories = $client->api('user')->repositories('suleigolden');

$github = new GithubClient($token, Utilities::env('GH_ACCOUNT'));
$board = new \KanbanBoard\Application($github, $repositories, array('waiting-for-feedback'));
$data = $board->board();
$m = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader('../views'),
));
echo $m->render('index', array('milestones' => $data));
