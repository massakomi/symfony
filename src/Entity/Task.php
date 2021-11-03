<?php
// src/Entity/Task.php
namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class Task
{

    /**
     * @Assert\NotBlank
     */
    public $task;

    public $createDate;
    public $x;

    /**
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     */
    protected $dueDate;

    /*public function getTask(): string
    {
        return $this->task;
    }

    public function setTask(string $task): void
    {
        $this->task = $task;
    }*/

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }
}