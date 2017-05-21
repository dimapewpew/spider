<?php


class ManagerCest
{
    private $manager_pid;

    // tests
    public function tryToLaunchManager(AcceptanceTester $I)
    {
        chdir('src');
        $this->manager_pid = shell_exec('/usr/bin/php manager.php > /dev/null 2>&1 & echo -n $!');
        sleep(1);
        $I->assertTrue(file_exists('/proc/' . $this->manager_pid));
    }

    public function tryToAddTask(AcceptanceTester $I)
    {
        $I->runShellCommand('/usr/bin/php commander.php --add --path=../tests/fixtures/crap --pattern=/.+/');
        $I->seeInShellOutput('Done');
    }

    public function tryToGetStatus(AcceptanceTester $I)
    {
        $I->runShellCommand('/usr/bin/php commander.php --status');
        $I->seeInShellOutput('Workers');
        $I->seeInShellOutput('Queue');
    }

    public function tryToClearQueue(AcceptanceTester $I)
    {
        $I->runShellCommand('/usr/bin/php commander.php --clear');
        $I->seeInShellOutput('Done');
    }

    public function tryToStopManager(AcceptanceTester $I)
    {
        $I->assertTrue(file_exists('/proc/' . $this->manager_pid));
        posix_kill($this->manager_pid, 9);
        sleep(1);
        $I->assertFalse(file_exists('/proc/' . $this->manager_pid));
    }
}
