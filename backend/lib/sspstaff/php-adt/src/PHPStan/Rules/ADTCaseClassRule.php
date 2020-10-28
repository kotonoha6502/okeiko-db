<?php


namespace SspStaff\ADT\PHPStan\Rules;


use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
class ADTCaseClassRule implements Rule
{
    /**
     * @var ADTHelper
     */
    private $adtHelper;

    public function __construct(\SspStaff\ADT\PHPStan\Rules\ADTHelper $adtHelper)
    {
        $this->adtHelper = $adtHelper;
    }

    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Node\Stmt\Class_ $node */
        if (!$this->adtHelper->isADTCaseClass($node)) {
            return [];
        }

        $adtClass = (string)$node->extends;
        if ($node->getAttribute('anonymousClass')) {
            return [sprintf('Anonymous class extends ADT %s.', $adtClass)];
        }

        $errors = [];
        if (!$node->isFinal()) {
            $errors[] = sprintf('Case-class %s should be declared final.',  (string)$node->namespacedName);
        }
        $namespace = $scope->getNamespace();
        if ($namespace === null || $namespace !== $adtClass) {
            $errors[] = sprintf(
                'Case-class %s of ADT %s should be declared in namespace %s, declared in %s.',
                $node->name->toString(), $adtClass, $adtClass, $namespace ?? 'global namespace'
            );
        }

        return $errors;
    }
}
