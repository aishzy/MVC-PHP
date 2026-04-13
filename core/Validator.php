<?php

    namespace Core;

    class Validator {
        private array $errors = [];

        public function validate(array $data, array $rules): bool {
            $this->errors = [];

            foreach ($rules as $field => $ruleString) {
                $value = $data[$field] ?? null;
                $ruleList = explode('|', $ruleString);

                foreach ($ruleList as $rule) {
                    [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);
                    $this->applyRule($field, $value, $ruleName, $param, $data);
                }
            }
            return empty($this->errors);
        }

        public function applyRule (string $field, mixed $value, string $rule, ?string $param, array $data = []): void {
            switch ($rule) {
                case 'required':
                    if ($value === null || $value === '') {
                        $this->errors[$field][] = "{$field} is required.";
                    }
                    break;
                case 'min':
                    if (strlen((string)$value) < (int)$param) {
                        $this->errors[$field][] = "{$field} must be at least {$param} characters.";
                    }
                    break;
                case 'max':
                    if (strlen((string)$value) > (int)$param) {
                        $this->errors[$field][] = "{$field} may not exceed {$param} characters.";
                    }
                    break;
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$field][] = "{$field} must be a valid email address.";
                    } 
                    break;
                case 'numeric':
                    if (!is_numeric($value)) {
                        $this->errors[$field][] = "{$field} must be numeric.";
                    }
                    break;
                case 'alpha':
                    if (!ctype_alpha((string)$value)) {
                        $this->errors[$field][] = "{$field} must contain only letters.";
                    }
                    break;
                case 'confirmed':
                    $confirmationField = "{$field}_confirmation";
                    if (($data[$confirmationField] ?? null) !== $value) {
                        $this->errors[$field][] = "{$field} confirmation does not match.";
                    }
                    break;
            }
        }

        public function errors(): array {
            return $this->errors;
        }

        public function passes(): bool {
            return empty($this->errors);
        }

        public function fails(): bool {
            return !empty($this->errors);
        }

        public function firstError(string $field): ?string {
            return $this->errors[$field][0] ?? null;
        }
    }
?>