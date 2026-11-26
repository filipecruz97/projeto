function digits(value) {
    return value.replace(/\D/g, '');
}

function formatAmount(input, cursor = input.selectionStart) {
    const value = input.value;
    const separator = value.indexOf(',');
    const integerPart = separator === -1 ? value : value.slice(0, separator);
    const whole = digits(integerPart).replace(/^0+(?=\d)/, '');
    const fraction = separator === -1 ? '' : digits(value.slice(separator + 1));

    if (!whole && separator === -1) {
        input.value = '';
        input.setCustomValidity('');
        return;
    }

    const integer = (whole || '0').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    input.value = `${integer},${fraction.slice(0, 2).padEnd(2, '0')}`;
    input.setCustomValidity(input.value === '0,00' ? 'Informe um valor de pelo menos R$ 0,01.' : '');

    if (cursor === null) {
        return;
    }

    let position;
    if (separator !== -1 && cursor > separator) {
        position = integer.length + 1 + Math.min(digits(value.slice(separator + 1, cursor)).length, 2);
    } else {
        // Grouping dots may have moved. Keep the caret beside the same digits on its right.
        const digitsOnRight = digits(integerPart.slice(cursor)).length;
        position = integer.length;
        let remaining = digitsOnRight;
        while (position > 0 && remaining > 0) {
            position--;
            if (integer[position] !== '.') {
                remaining--;
            }
        }
    }

    input.setSelectionRange(position, position);
}

function pastedAmount(text) {
    const amount = text.trim().replace(/^R\$\s*/, '');

    if (/^(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d{1,2})?$/.test(amount)) {
        return amount;
    }

    if (/^\d+\.\d{1,2}$/.test(amount)) {
        return amount.replace('.', ',');
    }

    return null;
}

function enableMoneyInput(input) {
    const initialAmount = input.value;
    input.type = 'text';
    input.inputMode = 'decimal';
    input.placeholder = '0,00';
    input.value = initialAmount && Number(initialAmount) >= 0
        ? Number(initialAmount).toFixed(2).replace('.', ',')
        : '';
    formatAmount(input, null);

    input.addEventListener('input', () => formatAmount(input));

    input.addEventListener('beforeinput', (event) => {
        const { data, inputType } = event;
        const start = input.selectionStart;
        const end = input.selectionEnd;
        const separator = input.value.indexOf(',');
        const wholeField = start === 0 && end === input.value.length;
        const insertingDigit = inputType === 'insertText' && /^\d$/.test(data);
        const deleting = inputType.startsWith('delete');
        const backspace = inputType === 'deleteContentBackward';
        const deleteForward = inputType === 'deleteContentForward';
        const adjacentCharacter = input.value[backspace ? start - 1 : start];

        if (inputType === 'insertText' && (data === ',' || data === '.')) {
            event.preventDefault();
            if (wholeField || separator === -1) {
                input.value = '0,00';
            }
            formatAmount(input, input.value.indexOf(',') + 1);
        } else if (start === end && (backspace || deleteForward) && /[.,]/.test(adjacentCharacter)) {
            event.preventDefault();
            const position = backspace ? start - 1 : start + 1;
            input.setSelectionRange(position, position);
        } else if (!wholeField && start <= separator && end > separator && (deleting || insertingDigit)) {
            event.preventDefault();
            const replacement = insertingDigit ? data : '';
            input.setRangeText(`${replacement},`, start, end, 'start');
            formatAmount(input, start + replacement.length);
        } else if (insertingDigit && start === end && separator !== -1 && start > separator) {
            event.preventDefault();
            if (start < input.value.length) {
                input.setRangeText(data, start, start + 1, 'end');
                formatAmount(input);
            }
        }
    });

    input.addEventListener('paste', (event) => {
        const amount = pastedAmount(event.clipboardData.getData('text'));
        const { selectionStart: start, selectionEnd: end } = input;
        const separator = input.value.indexOf(',');

        if (amount && /^\d+$/.test(amount) && (end <= separator || start > separator)) {
            return;
        }

        event.preventDefault();
        if (amount !== null) {
            input.value = amount;
            formatAmount(input, amount.length);
        }
    });

    input.form.addEventListener('formdata', (event) => {
        const amount = input.value.replaceAll('.', '').replace(',', '.');
        event.formData.set(input.name, amount);
    });
}

document.querySelectorAll('[data-money-input]').forEach(enableMoneyInput);
