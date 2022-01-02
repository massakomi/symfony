<?php
// src/Entity/Task.php
namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class Task
{

    public $createDate;

    /**
     * @Assert\NotBlank
     */
    public $task;

    /**
     * @Assert\Blank
     */
    public $emptyVal;

    /**
     * @Assert\NotNull
     */
    public $notNull;

    /**
     * @Assert\IsNull
     */
    public $onlyNull;

    /**
     * @Assert\IsTrue
     */
    public $onlyTrue;

    /**
     * @Assert\IsFalse
     */
    public $onlyFalse;

    /**
     * @Assert\Type("integer")
     */
    public $onlyInts;





    /**
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     */
    protected $dueDate;

    public function getTask(): string
    {
        return $this->task;
    }

    public function setTask(string $task): void
    {
        $this->task = $task;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }
}