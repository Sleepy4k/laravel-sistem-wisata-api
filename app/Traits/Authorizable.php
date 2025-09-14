<?php

namespace App\Traits;

use App\Facades\ApiResponse;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

trait Authorizable
{
    /**
     * Override of callAction to perform the authorization before.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function callAction($method, $parameters): mixed
    {
        [$ability, $policyParams] = $this->resolveAbilityAndPolicyParams($method, $parameters);
        $paramValue = is_array($policyParams) ? array_values($policyParams) : $policyParams;

        if (Gate::denies($ability, $paramValue)) {
            return ApiResponse::error('You do not have permission to perform this action.', 403, [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'message' => "User lacks the required ability: {$ability}",
            ]);
        }

        return parent::callAction($method, $parameters);
    }

    /**
     * Resolve the ability and ordered parameters for the policy method.
     *
     * @param string $method
     * @param array $parameters
     * @return array [ability, orderedParams]
     */
    protected function resolveAbilityAndPolicyParams($method, $parameters): array
    {
        $orderedParams = [];
        $abilities = $this->getAbilities();
        $policyMethod = $abilities[$method] ?? $method;
        $policyClass = $this->getPolicyClass();

        if (is_subclass_of($policyClass, Model::class)) {
            $policyClass = Gate::getPolicyFor($policyClass);
        }

        if ($policyClass && method_exists($policyClass, $policyMethod)) {
            $reflection = new \ReflectionMethod($policyClass, $policyMethod);
            $policyParams = $reflection->getParameters();
            $usedIndexes = [];

            foreach ($policyParams as $paramIndex => $param) {
                $type = $param->getType();
                $typeName = $type instanceof \ReflectionNamedType ? $type->getName() : null;

                // Skip User model parameters as they are set by default from Laravel
                if ($paramIndex === 0 && $typeName && is_a($typeName, User::class, true)) {
                    continue;
                }

                $matched = null;
                foreach ($parameters as $i => $controllerParam) {
                    if (in_array($i, $usedIndexes, true)) continue;

                    if ($type && !$type->isBuiltin() && $typeName) {
                        if (is_object($controllerParam) && is_a($controllerParam, $typeName)) {
                            $matched = $controllerParam;
                            $usedIndexes[] = $i;
                            break;
                        }
                    } else {
                        // For scalar types or no type
                        $matched = $controllerParam;
                        $usedIndexes[] = $i;
                        break;
                    }
                }

                // Add matched parameter to ordered params
                if ($matched !== null) {
                    $orderedParams[] = $matched;
                } elseif ($param->isDefaultValueAvailable()) {
                    $orderedParams[] = $param->getDefaultValue();
                }
            }
        }

        // Return policy class with passed parameters, or just policy class if no parameters
        if (!empty($orderedParams)) {
            $orderedParams = [$this->getPolicyClass(), ...$orderedParams];
        } else {
            $orderedParams = $this->getPolicyClass();
        }

        return [$policyMethod, $orderedParams];
    }

    /**
     * Find an instance of the binding class in the parameters.
     *
     * @param array $parameters
     * @param string $bindingClass
     * @return mixed|null
     */
    protected function findInstanceInParameters($parameters, $bindingClass): mixed
    {
        foreach ($parameters as $param) {
            if (is_object($param) && is_a($param, $bindingClass)) {
                return $param;
            }
        }
        return null;
    }

    /**
     * Instantiate the binding class if possible.
     *
     * @param string $bindingClass
     * @return mixed|null
     */
    protected function instantiateBindingClass($bindingClass): mixed
    {
        try {
            return new $bindingClass;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Find a model instance in the parameters (returns first found).
     *
     * @param array $parameters
     * @return mixed|null
     */
    protected function findModelInParameters($parameters): mixed
    {
        foreach ($parameters as $param) {
            if (is_object($param) && is_subclass_of($param, \Illuminate\Database\Eloquent\Model::class)) {
                return $param;
            }
        }
        return null;
    }

    /**
     * Get the policy class from the controller property.
     *
     * @return string|null
     */
    protected function getPolicyClass(): ?string
    {
        return property_exists($this, 'policy') ? $this->policy : null;
    }

    /**
     * Get the abilities for the controller from property.
     *
     * @return array
     */
    private function getAbilities(): array
    {
        return property_exists($this, 'abilities') ? $this->abilities : [];
    }
}
