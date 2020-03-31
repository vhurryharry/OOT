import { Component, OnInit, Input, Output, EventEmitter } from "@angular/core";

import { loadStripe } from "@stripe/stripe-js";
import { environment } from "src/environments/environment";
import { PaymentService } from "src/app/services/payment.service";

@Component({
  selector: "app-card",
  templateUrl: "./card.component.html",
  styleUrls: ["./card.component.scss"]
})
export class CardComponent implements OnInit {
  @Input()
  public clientSecret = "";

  @Input()
  public buttonLabel = "Place Order";

  @Output()
  tokenReady = new EventEmitter();

  @Output()
  errorOccured = new EventEmitter();

  @Output()
  startLoading = new EventEmitter();

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
    } else if (result.error) {
      this.errorOccured.emit(result.error.message);
      this.error = result.error.message;
    }
  }

  setBrandIcon(brand) {
    this.brandIcon = this.paymentService.getPaymentIcon(brand);
  }

  async submit() {
    this.startLoading.emit();

    const { setupIntent, error } = await this.stripe.confirmCardSetup(
      this.clientSecret,
      {
        payment_method: {
          card: this.cardNumberElement
        }
      }
    );

    if (error) {
      // Display error.message in your UI.
      this.errorOccured.emit(error.message);
    } else {
      if (setupIntent.status === "succeeded") {
        // The setup has succeeded. Display a success message. Send
        // setupIntent.payment_method to your server to save the card to a Customer
        console.log(setupIntent.payment_method);

        this.tokenReady.emit(setupIntent.payment_method);
      } else {
        this.errorOccured.emit("Unexpected error occured!");
      }
    }

    // this.stripe.createToken(this.cardNumberElement, data).then(result => {
    //   this.loading = false;
    //   this.setOutcome(result);
    // });
  }
}
