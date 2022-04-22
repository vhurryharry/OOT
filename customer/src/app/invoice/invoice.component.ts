import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { jsPDF } from "jspdf";
// import * as jsPDF from "jspdf";

import { countryList } from "../services/constants";
import { PaymentService } from "../services/payment.service";
import { LoginService } from "../services/login.service";

@Component({
  selector: "app-invoice",
  templateUrl: "./invoice.component.html",
  styleUrls: ["./invoice.component.scss"],
})
export class InvoiceComponent implements OnInit {
  number: string;
  dataLoaded = false;
  billing: any;
  error: string;

  invoiceNumber = "";

  constructor(
    private route: ActivatedRoute,
    private loginService: LoginService,
    private paymentService: PaymentService
  ) {
    this.route.params.subscribe((params) => {
      this.number = params.number;
      let user = this.loginService.getCurrentUserId();
      user = user.slice(0, 5).toUpperCase();
      this.invoiceNumber =
        user + Math.floor(new Date().getTime() % 100000).toString();

      this.paymentService.getBilling(this.number).subscribe((result: any) => {
        this.dataLoaded = true;

        if (result.success) {
          this.billing = result.billing;

          this.billing.created = new Date(this.billing.created);
          this.billing.invoice.created_at = new Date(
            this.billing.invoice.created_at
          );

          const shippingCountry = countryList.find((country) => {
            return country.code === this.billing.shipping.address.country;
          });

          this.billing.shipping.address.country = shippingCountry.name;

          this.billing.items = this.billing.items.filter(
            (item) => item.amount > 0
          );
        } else {
          this.error = result.error;
        }
      });
    });
  }

  ngOnInit() {}

  download() {
    const pdf = new jsPDF("p", "px", [816, 1076]);

    pdf.html(document.getElementById("invoice-pdf-content"), {
      callback: (pdf) => {
        pdf.save("invoice.pdf");
      },
    });
  }
}
