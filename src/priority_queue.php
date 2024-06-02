<?php
class Node
{
    public int $data;
    public ?Node $next;
    public function __construct(int $val)
    {
        $this->data = $val;
        $this->next = null;
    }
}

class QueueHolder
{
    public ?Node $front;
    public ?Node $rear;
    public function __construct(?Node $front, ?Node $rear)
    {
        $this->front = $front;
        $this->rear = $rear;
    }
}

class Priorityqueue
{
    public array $map;
    public array $priorityMap;

    public function __construct()
    {
        $this->priorityMap = [];
    }

    public function enqueue(int $x, int $priority): self
    {
        if (empty($this->map[$priority])) {
            $this->map[$priority] = new QueueHolder(null, null);
            $this->priorityMap[] = $priority;
            rsort($this->priorityMap);
        }

        $newNode = new Node($x);

        if (!$this->map[$priority]->front) {
            $this->map[$priority]->front = $newNode;
            $this->map[$priority]->rear = $this->map[$priority]->front;
            return $this;
        }

        $this->map[$priority]->rear->next = $newNode;
        $this->map[$priority]->rear = $newNode;
        return $this;
    }

    public function dequeue(): self
    {
        $dequeuePriority = 0;
        $length = count($this->priorityMap);
        for ($i = 0; $i < $length; $i++) {
            if ($this->isEmpty($this->priorityMap[$i])) {
                $dequeuePriority = $this->priorityMap[$i];
                break;
            }
        }

        if (!$this->map[$dequeuePriority]->front) {
            return $this;
        }

        $this->map[$dequeuePriority]->front =
            $this->map[$dequeuePriority]->front->next;

        return $this;
    }

    public function isEmpty(int $queuePriority): bool
    {
        return $this->map[$queuePriority]->front != null &&
            $this->map[$queuePriority]->rear != null;
    }

    public function displayQueue(int $q)
    {
        if (!$this->isEmpty($q)) {
            return;
        }

        $temp = $this->map[$q]->front;

        while ($temp) {
            echo "\n" . $temp->data . "\n";
            $temp = $temp->next;
        }
    }
}

$queue = new Priorityqueue();

$queue->enqueue(3, 2);
$queue->enqueue(7, 1);
$queue->enqueue(10, 1);
$queue->enqueue(4, 1);
$queue->enqueue(50, 7);
$queue->dequeue();
$queue->dequeue();
$queue->dequeue();
$queue->displayQueue(1);
