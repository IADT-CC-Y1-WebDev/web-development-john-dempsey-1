class Validator {
    #data;
    #rules;
    #errors = {};
    #customMessages;

    constructor(data, rules, customMessages = {}) {
        this.#data = data;
        this.#rules = rules;
        this.#customMessages = customMessages;
        this.#validate();
    }

    #validate() {
        for (const [field, ruleString] of Object.entries(this.#rules)) {
            const rules = this.#parseRules(ruleString);
            const value = this.#getValue(field);

            for (const rule of rules) {
                this.#applyRule(field, value, rule);
            }
        }
    }

    #parseRules(ruleString) {
        if (Array.isArray(ruleString)) {
            return ruleString;
        }

        return ruleString.split('|').map(rulePart => {
            rulePart = rulePart.trim();
            if (rulePart.includes(':')) {
                const colonIndex = rulePart.indexOf(':');
                return {
                    name: rulePart.slice(0, colonIndex),
                    value: rulePart.slice(colonIndex + 1)
                };
            }
            return { name: rulePart, value: null };
        });
    }

    #getValue(field) {
        return field in this.#data ? this.#data[field] : null;
    }

    #applyRule(field, value, rule) {
        const { name, value: ruleValue } = rule;

        switch (name) {
            case 'required':      this.#validateRequired(field, value); break;
            case 'notempty':      this.#validateNotEmpty(field, value); break;
            case 'array':         this.#validateArray(field, value); break;
            case 'min':           this.#validateMin(field, value, ruleValue); break;
            case 'max':           this.#validateMax(field, value, ruleValue); break;
            case 'email':         this.#validateEmail(field, value); break;
            case 'float':         this.#validateFloat(field, value); break;
            case 'integer':       this.#validateInteger(field, value); break;
            case 'minvalue':      this.#validateMinValue(field, value, ruleValue); break;
            case 'maxvalue':      this.#validateMaxValue(field, value, ruleValue); break;
            case 'boolean':       this.#validateBoolean(field, value); break;
            case 'regex':         this.#validateRegex(field, value, ruleValue); break;
            case 'in':            this.#validateIn(field, value, ruleValue); break;
            case 'subset':        this.#validateSubset(field, value, ruleValue); break;
        }
    }

    #validateRequired(field, value) {
        if (value === null || value === '' || (Array.isArray(value) && value.length === 0)) {
            this.#addError(field, `The ${field} field is required.`);
        }
    }

    #validateNotEmpty(field, value) {
        if (value === '' || (Array.isArray(value) && value.length === 0)) {
            this.#addError(field, `The ${field} field must not be empty.`);
        }
    }

    #validateArray(field, value) {
        if (value !== null && value !== '' && !Array.isArray(value)) {
            this.#addError(field, `The ${field} must be an array.`);
        }
    }

    #validateMin(field, value, min) {
        if (value === null || value === '') return;

        min = Number(min);
        const length = Array.isArray(value) ? value.length : String(value).length;

        if (length < min) {
            if (Array.isArray(value)) {
                this.#addError(field, `The ${field} must have at least ${min} items.`);
            } else {
                this.#addError(field, `The ${field} must be at least ${min} characters.`);
            }
        }
    }

    #validateMax(field, value, max) {
        if (value === null || value === '') return;

        max = Number(max);
        const length = Array.isArray(value) ? value.length : String(value).length;

        if (length > max) {
            if (Array.isArray(value)) {
                this.#addError(field, `The ${field} must not have more than ${max} items.`);
            } else {
                this.#addError(field, `The ${field} must not exceed ${max} characters.`);
            }
        }
    }

    #validateEmail(field, value) {
        if (value === null || value === '') return;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (!emailRegex.test(item)) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be valid email addresses.`
                    : `The ${field} must be a valid email address.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateFloat(field, value) {
        if (value === null || value === '') return;

        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (isNaN(item) || isNaN(parseFloat(item))) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be valid floats.`
                    : `The ${field} must be a valid float.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateInteger(field, value) {
        if (value === null || value === '') return;

        const isInt = v => Number.isInteger(Number(v)) && !isNaN(v) && String(v).trim() !== '';
        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (!isInt(item)) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be integers.`
                    : `The ${field} must be an integer.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateMinValue(field, value, min) {
        if (value === null || value === '') return;

        min = Number(min);
        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (isNaN(item)) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be numeric to validate minimum value.`
                    : `The ${field} must be numeric to validate minimum value.`;
                this.#addError(field, msg);
                return;
            }
            if (Number(item) < min) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be at least ${min}.`
                    : `The ${field} must be at least ${min}.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateMaxValue(field, value, max) {
        if (value === null || value === '') return;

        max = Number(max);
        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (isNaN(item)) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be numeric to validate maximum value.`
                    : `The ${field} must be numeric to validate maximum value.`;
                this.#addError(field, msg);
                return;
            }
            if (Number(item) > max) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must not exceed ${max}.`
                    : `The ${field} must not exceed ${max}.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateBoolean(field, value) {
        if (value === null || value === '') return;

        const booleanValues = [true, false, 0, 1, '0', '1', 'true', 'false', 'True', 'False'];
        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (!booleanValues.includes(item)) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be boolean values.`
                    : `The ${field} must be a boolean value.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateRegex(field, value, pattern) {
        if (value === null || value === '') return;

        const regex = new RegExp(pattern);
        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (!regex.test(item)) {
                const msg = Array.isArray(value)
                    ? `All values in ${field} must match the required format.`
                    : `The ${field} format is invalid.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateIn(field, value, allowedValues) {
        if (value === null || value === '') return;

        const allowedArray = allowedValues.split(',').map(v => v.trim());
        const items = Array.isArray(value) ? value : [value];

        for (const item of items) {
            if (!allowedArray.includes(String(item))) {
                const allowed = allowedArray.join(', ');
                const msg = Array.isArray(value)
                    ? `All values in ${field} must be one of: ${allowed}.`
                    : `The ${field} must be one of: ${allowed}.`;
                this.#addError(field, msg);
                break;
            }
        }
    }

    #validateSubset(field, value, allowedValues) {
        if (value === null || value === '') return;

        if (!Array.isArray(value)) {
            this.#addError(field, `The ${field} must be an array for subset validation.`);
            return;
        }

        const allowedArray = allowedValues.split(',').map(v => v.trim());

        for (const item of value) {
            if (!allowedArray.includes(String(item))) {
                const allowed = allowedArray.join(', ');
                this.#addError(field, `All values in ${field} must be one of: ${allowed}.`);
                break;
            }
        }
    }

    #addError(field, message) {
        if (field in this.#customMessages) {
            message = this.#customMessages[field];
        }

        if (!(field in this.#errors)) {
            this.#errors[field] = [];
        }

        this.#errors[field].push(message);
    }

    fails() {
        return Object.keys(this.#errors).length > 0;
    }

    passes() {
        return Object.keys(this.#errors).length === 0;
    }

    errors() {
        return this.#errors;
    }

    firstError(field = null) {
        if (field) {
            return this.#errors[field]?.[0] ?? null;
        }

        for (const fieldErrors of Object.values(this.#errors)) {
            return fieldErrors[0];
        }

        return null;
    }

    allErrors() {
        return this.#errors;
    }
}
