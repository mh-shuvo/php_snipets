<?php

class MysqlBuilderQuery
{
    private array $query = [
        'select' => [],
        'from' => null,
        'where' => [],
        'bindings' => []
    ];

    public function select(string ...$columns): self
    {
        $this->query['select'] = array_merge($this->query['select'], $columns);
        return $this;
    }

    public function where(string $key, mixed $value): self
    {
        if ($value instanceof Closure) {
            $nestedQuery = new self();
            $value($nestedQuery);

            $this->query['where'][] = '(' . $nestedQuery->toSql() . ')';
            $this->query['bindings'] = array_merge($this->query['bindings'], $nestedQuery->getBindings());
        } else {
            $this->query['where'][] = "$key = ?";
            $this->query['bindings'][] = $value;
        }

        return $this;
    }

    public function orWhere(string $key, mixed $value): self
    {
        if ($value instanceof Closure) {
            $nestedQuery = new self();
            $value($nestedQuery);

            $this->query['where'][] = 'OR (' . $nestedQuery->toSql() . ')';
            $this->query['bindings'] = array_merge($this->query['bindings'], $nestedQuery->getBindings());
        } else {
            $this->query['where'][] = 'OR ' . "$key = ?";
            $this->query['bindings'][] = $value;
        }

        return $this;
    }

    public function whereIn(string $key, $values): self
    {
        if (is_array($values)) {
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $this->query['where'][] = "$key IN ($placeholders)";
            $this->query['bindings'] = array_merge($this->query['bindings'], $values);
        } elseif ($values instanceof Closure) {
            $nestedQuery = new self();
            $values($nestedQuery);

            $this->query['where'][] = "$key IN (" . $nestedQuery->toSql() . ')';
            $this->query['bindings'] = array_merge($this->query['bindings'], $nestedQuery->getBindings());
        } else {
            throw new InvalidArgumentException('Invalid argument for whereIn method.');
        }

        return $this;
    }

    public function from(string $table): self
    {
        $this->query['from'] = $table;
        return $this;
    }

    public function toSql(): string
    {
        $query = 'SELECT ' . (empty($this->query['select']) ? '*' : implode(', ', $this->query['select']));
        $query .= ' FROM ' . $this->query['from'];

        if (!empty($this->query['where'])) {
            $query .= ' WHERE ' . implode(' AND ', $this->query['where']);
        }

        return $query;
    }

    public function getBindings(): array
    {
        return $this->query['bindings'];
    }
}



// Example usage:
$query = new MysqlBuilderQuery();
$query
    ->select('ename')
    ->from('employee')
    ->whereIn('id', [1, 2, 3])
    ->orWhere('name', 'John')
    ->orWhere('dept', function (MysqlBuilderQuery $query) {
        $query
            ->select('dno')
            ->from('dept')
            ->where('floor', function (MysqlBuilderQuery $query) {
                $query->select('floor_number')
                    ->from('floors')
                    ->where('name', 'Ground')
                    ->whereIn('ids', function (MysqlBuilderQuery $query) {
                        $query
                            ->select('id')
                            ->from('products')
                            ->where('id', 5);
                    });
            });
    })
    ->orWhere('dept', function (MysqlBuilderQuery $query) {
        $query->select('dno')
            ->from('dept')
            ->whereIn('floor', [1, 2, 3]);
    });

echo $query->toSql();
//$query->getBindings();
