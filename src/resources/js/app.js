import './bootstrap'; // если есть axios и csrf
import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.default.css"; // подключаем CSS TomSelect
import flatpickr from "flatpickr";
import { Russian } from "flatpickr/dist/l10n/ru.js";
import "../css/app.css";

document.addEventListener('DOMContentLoaded', () => {
    // TomSelect
    document.querySelectorAll('.js-select').forEach((el) => {
        new TomSelect(el, {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: 'Начните вводить...',
            // keyAttr, labelField, valueField по необходимости
        });

        const selected = el.dataset.selected;
        if (selected) {
            el.tomselect?.setValue(selected, true);
        }
    });

    // Flatpickr
    document.querySelectorAll('.flatpickr-date').forEach(el => {
        flatpickr(el, {
            locale: Russian,
            dateFormat: "d.m.Y",
            defaultDate: el.dataset.default || null,
        });
    });
});
