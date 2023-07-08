<?php declare(strict_types=1);

namespace danog\MadelineProto\EventHandler\Filter\Combinator;

use danog\MadelineProto\EventHandler;
use danog\MadelineProto\EventHandler\Filter\Filter;
use danog\MadelineProto\EventHandler\Filter\FilterAllowAll;
use danog\MadelineProto\EventHandler\Update;
use Webmozart\Assert\Assert;

/**
 * ANDs multiple filters.
 */
final class FiltersAnd extends Filter
{
    /** @var array<Filter> */
    private readonly array $filters;
    public function __construct(Filter ...$filters)
    {
        Assert::notEmpty($filters);
        $this->filters = $filters;
    }
    public function initialize(EventHandler $API): Filter
    {
        $final = [];
        foreach ($this->filters as $filter) {
            $filter = $filter->initialize($API) ?? $filter;
            if ($filter instanceof self) {
                $final = \array_merge($final, $filter->filters);
            }
        }
        $final = \array_filter(
            $final,
            fn (Filter $f): bool => !$f instanceof FilterAllowAll,
        );
        $final = \array_values($final);
        if (\count($final) === 1) {
            return $final[0];
        }
        return new self(...$final);
    }
    public function apply(Update $update): bool
    {
        foreach ($this->filters as $filter) {
            if (!$filter->apply($update)) {
                return false;
            }
        }
        return true;
    }
}
