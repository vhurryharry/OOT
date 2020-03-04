import { Component, OnInit, Input, Output, EventEmitter } from "@angular/core";
import { FormGroup, FormBuilder, Validators } from "@angular/forms";

import {
  StripeService,
  Elements,
  Element as StripeElement,
  ElementsOptions
} from "ngx-stripe";

@Component({
  selector: "app-payment",
  templateUrl: "./payment.component.html",
  styleUrls: ["./payment.component.scss"]
})
export class PaymentComponent implements OnInit {
  @Input()
  public askUserInfo = false;

  @Input()
  public userName = "";

  @Output()
  tokenReady = new EventEmitter();

  elements: Elements;
  card: StripeElement;
  error: string = null;

  // optional parameters
  elementsOptions: ElementsOptions = {
    locale: "auto"
  };

  payment: FormGroup;

  loading = false;

  constructor(private fb: FormBuilder, private stripeService: StripeService) {}

  ngOnInit() {
    this.payment = this.fb.group({
      userName: [this.userName, [Validators.required]]
    });

    this.stripeService.elements(this.elementsOptions).subscribe(elements => {
      this.elements = elements;
      // Only mount the element the first time
      if (!this.card) {
        this.card = this.elements.create("card", {
          style: {
            base: {
              iconColor: "#666EE8",
              color: "#31325F",
              lineHeight: "60px",
              fontWeight: 300,
              fontSize: "18px",
              "::placeholder": {
                color: "#CFD7E0"
              }
            }
          }
        });
        this.card.mount("#card-element");
      }
    });
  }

  submit() {
    const name = this.payment.get("userName").value;

    this.error = null;
    this.loading = true;

    this.stripeService.createToken(this.card, { name }).subscribe(result => {
      this.loading = false;

      if (result.token) {
        // Use the token to create a charge or a customer
        // https://stripe.com/docs/charges

        this.tokenReady.emit(result.token);
      } else if (result.error) {
        // Error creating the token
        this.error = result.error.message;
      }
    });
  }
}
