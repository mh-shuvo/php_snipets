<?php

class Command
{
    private ?Closure $skipClosure;
    private string $command;
    private string $email;

    public function __construct(string $command)
    {
        $this->skipClosure = static fn() => false;
        $this->command = $command;
    }

    public function skip(Closure $closure):self
    {
        $this->skipClosure = $closure;
        return $this;
    }

    public function setEmail(string $email):self
    {
        $this->email = $email;
        return $this;
    }

    public function run():string | null | false
    {
        $skip = call_user_func($this->skipClosure);

        if($skip){
            return false;
        }

        $result = shell_exec($this->command);
        if($result == false){
            // send email to $this->email
            return "the error is send to $this->email";
        }
        return $result;
    }

}

readonly class Calendar
 
{
    const SATURDAY = 'Sat';
    const SUNDAY = 'Sun';
    private DateTime $date;
 
    /**
     * @throws Exception
     */
    public function __construct(string $date = 'now')
    {
        $this->date = new DateTime($date);
    }
 
    public function isSunday(): bool
    {
        return self::SUNDAY === $this->day();
    }
 
    public function isSaturday(): bool
    {
        return self::SATURDAY === $this->day();
    }
 
    public function isWeekend(): bool
    {
        return in_array($this->day(), [self::SATURDAY, self::SUNDAY]);
    }
 
    public function isWeekday(): bool
    {
        return !$this->isWeekend();
    }
 
    /**
     * @return string
     */
    private function day(): string
    {
        return $this->date->format('D');
    }
}

class CommandManager
{
    /**
     * @var Command[] $commands
     */

     private array $commands = [];

     public function command(string $command):command{
        return $this->commands[] = new Command($command);
     }

     public function getCommands(): array
    {
        return $this->commands;
    }
}

class Schedule
{
    public static function command(CommandManager $manager):void
    {
        $manager->command('ls')
        ->setEmail("hasan@gmail.com");

        
        $manager->command('dir')
        ->setEmail("hasan@gmail.com")
        ->skip(function(){
            return (new Calendar())->isSunday();
        });
    }
}

Schedule::command($commandManager = new CommandManager());
 
foreach ($commandManager->getCommands() as $command) {
 
    echo '----------------------------------------------------------------' . PHP_EOL;
    echo $command->run();
}