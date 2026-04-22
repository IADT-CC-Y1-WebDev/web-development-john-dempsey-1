class TriStateDropdown extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
    }

    connectedCallback() {
        const baseLabel = this.getAttribute('label') || 'Select';

        this.shadowRoot.innerHTML = `
            <style>
                :host {
                    position: relative;
                    display: inline-block;
                }
                .toggle {
                    font-size: 13px;
                    min-width: 160px;
                    background-color: #fff;
                    color: #000;
                    border: 1px solid #aaa;
                    border-radius: 3px;
                    padding: 5px 10px;
                    cursor: pointer;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 8px;
                }
                .toggle::after {
                    content: '▾';
                    font-size: 14px;
                }
                .panel {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    z-index: 100;
                    background: #fff;
                    border: 1px solid #aaa;
                    border-radius: 5px;
                    padding: 10px;
                    min-width: 220px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                }
                .panel.open {
                    display: block;
                }
            </style>
            <button type="button" class="toggle">${baseLabel}</button>
            <div class="panel">
                <slot></slot>
            </div>
        `;

        this._toggle = this.shadowRoot.querySelector('.toggle');
        this._panel = this.shadowRoot.querySelector('.panel');
        this._baseLabel = baseLabel;

        this._toggle.addEventListener('click', () => {
            this._panel.classList.toggle('open');
        });

        document.addEventListener('click', (e) => {
            if (!this.contains(e.target)) {
                this._panel.classList.remove('open');
            }
        });

        this.addEventListener('tri-state-change', () => this._updateLabel());
    }

    _updateLabel() {
        const included = this.querySelectorAll('tri-state-checkbox[data-state="1"]').length;
        const excluded = this.querySelectorAll('tri-state-checkbox[data-state="2"]').length;
        let label = this._baseLabel;
        if (included > 0 || excluded > 0) {
            const parts = [];
            if (included > 0) parts.push(included + ' ✓');
            if (excluded > 0) parts.push(excluded + ' ✗');
            label += ' (' + parts.join(' ') + ')';
        }
        this._toggle.textContent = label;
    }

    getSelections() {
        const included = [];
        const excluded = [];
        this.querySelectorAll('tri-state-checkbox').forEach(cb => {
            if (cb.state === 1) included.push(cb.value);
            if (cb.state === 2) excluded.push(cb.value);
        });
        return { included, excluded };
    }

    reset() {
        this.querySelectorAll('tri-state-checkbox').forEach(cb => cb.reset());
        this._toggle.textContent = this._baseLabel;
    }
}

customElements.define('tri-state-dropdown', TriStateDropdown);
