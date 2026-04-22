class TriStateCheckbox extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this._state = 0;
    }

    connectedCallback() {
        this.shadowRoot.innerHTML = `
            <style>
                :host {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    cursor: pointer;
                    font-size: 12px;
                    color: #444;
                    padding: 2px 0;
                    user-select: none;
                }
                .box {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 16px;
                    height: 16px;
                    border: 2px solid #aaa;
                    border-radius: 3px;
                    background-color: #fff;
                    flex-shrink: 0;
                    font-size: 11px;
                    color: #fff;
                    transition: background-color 0.2s, border-color 0.2s;
                }
                :host([data-state="1"]) .box {
                    background-color: #1a7a1a;
                    border-color: #4bff3c;
                }
                :host([data-state="2"]) .box {
                    background-color: #8b0000;
                    border-color: #ff4c4c;
                }
            </style>
            <span class="box"></span>
            <slot></slot>
        `;

        this._box = this.shadowRoot.querySelector('.box');
        this._updateBox();

        this.addEventListener('click', () => {
            this._state = (this._state + 1) % 3;
            this.dataset.state = this._state;
            this._updateBox();
            this.dispatchEvent(new CustomEvent('tri-state-change', { bubbles: true }));
        });
    }

    _updateBox() {
        const labels = ['', '✓', '✗'];
        this._box.textContent = labels[this._state];
    }

    get state() { return this._state; }
    get value() { return this.getAttribute('value'); }

    reset() {
        this._state = 0;
        this.dataset.state = 0;
        this._updateBox();
    }
}

customElements.define('tri-state-checkbox', TriStateCheckbox);