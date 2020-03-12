import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";

import * as jsPDF from "jspdf";
import { PaymentService } from "../services/payment.service";

@Component({
  selector: "app-invoice",
  templateUrl: "./invoice.component.html",
  styleUrls: ["./invoice.component.scss"]
})
export class InvoiceComponent implements OnInit {
  number: string;
  dataLoaded = false;
  billing: any;
  error: string;

  constructor(
    private route: ActivatedRoute,
    private paymentService: PaymentService
  ) {
    this.route.params.subscribe(params => {
      this.number = params.number;

      this.paymentService.getBilling(this.number).subscribe((result: any) => {
        this.dataLoaded = true;

        if (result.success) {
          this.billing = result.billing;

          this.billing.created = new Date(this.billing.created);
          this.billing.invoice.created_at = new Date(
            this.billing.invoice.created_at
          );
          this.billing.items = this.billing.items.filter(
            item => item.amount > 0
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

    pdf.addHTML(document.querySelector("#invoice-pdf-content"), () => {
      pdf.save("web.pdf");
    });
  }
}
