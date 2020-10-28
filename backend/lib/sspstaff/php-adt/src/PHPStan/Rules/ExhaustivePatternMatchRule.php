<?php

namespace SspStaff\ADT\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Generic\TemplateObjectType;
use PHPStan\Type\ObjectType;
use SspStaff\ADT\MatchExpression;

/**
 * Class ExhaustivePatternMatchRule
 * @package SspStaff\ADT\PHPStan\Rules
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
class ExhaustivePatternMatchRule implements Rule
{

    /**
     * @var ADTHelper
     */
    private $adtHelper;

    /**
     * @var Broker
     */
    private $broker;

    public function __construct (ADTHelper $adtHelper, Broker $broker) {
        $this->adtHelper = $adtHelper;
        $this->broker = $broker;
    }
    public function getNodeType(): string
    {
        return Node\Expr\MethodCall::class;
    }

    /**
     * @param Node $node
     * @param Scope $scope
     * @return array|string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Node\Expr\MethodCall $node */
        $calledOnTypes = $scope->getType($node->var);
        if (!$calledOnTypes instanceof GenericObjectType ||
            $calledOnTypes->getClassName() !== MatchExpression::class ||
            !$node->name instanceof Node\Identifier ||
            $node->name->name !== 'on'
        ) {
            return [];
        }
        $onArg = $scope->getType($node->args[0]->value);
        if (!$onArg instanceof ObjectType) {
            return [
                'Method '.MatchExpression::class.'::on expects ADT, %s given.'
            ];
        }
        $scope->getType($node->args[0]);
        $scope->getDefinedVariables
        $adtClass = $calledOnTypes->getTypes()[0];
        if ($this->adtHelper->isADTClassByClassName($adtClass->getReferencedClasses()))
        $caseList = [];
        $targetADTType = $calledOnTypes->getTypes()[0];

        return ['hoge'];
    }
}
