import { Component, OnInit, Input, Output, EventEmitter } from "@angular/core";

import { loadStripe } from "@stripe/stripe-js";
import { environment } from "src/environments/environment";
import { PaymentService } from "src/app/services/payment.service";

@Component({
  selector: "app-payment",
  templateUrl: "./payment.component.html",
  styleUrls: ["./payment.component.scss"]
})
export class PaymentComponent implements OnInit {
  @Input()
  public askUserInfo = false;
  @Input()
  public name = "";
  @Input()
  public addressLine1 = "";
  @Input()
  public addressLine2 = "";
  @Input()
  public addressCity = "";
  @Input()
  public addressState = "";
  @Input()
  public addressZip = "";
  @Input()
  public addressCountry = "";

  @Input()
  public buttonLabel = "Place Order";

  @Output()
  tokenReady = new EventEmitter();

  private stripe: any;

  private cardNumberElement: any;
  private cardExpiryElement: any;
  private cardCvcElement: any;

  loading = false;
  error: string = null;
  brandIcon = "";

  constructor(private paymentService: PaymentService) {
    this.brandIcon = this.paymentService.getPaymentIcon("unknown");
  }

  async ngOnInit() {
    this.stripe = await loadStripe(environment.stripePKey);

    const elements = this.stripe.elements();

    const style = {
      base: {
        lineHeight: "27px",
        fontWeight: "normal",
        fontSize: "18px"
      }
    };

    this.cardNumberElement = elements.create("cardNumber", { style });
    this.cardNumberElement.mount("#card-number-element");

    this.cardExpiryElement = elements.create("cardExpiry", { style });
    this.cardExpiryElement.mount("#card-expiry-element");

    this.cardCvcElement = elements.create("cardCvc", { style });
    this.cardCvcElement.mount("#card-cvc-element");

    this.cardNumberElement.on("change", event => {
      // Switch brand logo
      if (event.brand) {
        this.setBrandIcon(event.brand);
      }

      this.setOutcome(event);
    });
  }

  setOutcome(result) {
    if (result.token) {
      this.tokenReady.emit(result.token);
    } else if (result.error) {
      this.error = result.error.message;
    }
  }

  setBrandIcon(brand) {
    this.brandIcon = this.paymentService.getPaymentIcon(brand);
  }

  submit() {
    this.loading = true;
    const data = {
      name: this.name,
      address_line1: this.addressLine1,
      address_line2: this.addressLine2,
      address_city: this.addressCity,
      address_state: this.addressState,
      address_zip: this.addressZip,
      address_country: this.addressCountry
    };

    this.stripe.createToken(this.cardNumberElement, data).then(result => {
      this.loading = false;
      this.setOutcome(result);
    });
  }
}
