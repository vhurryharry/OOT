import { Component, OnInit } from "@angular/core";
import { FAQService } from "./faq.service";

@Component({
  selector: "app-faq",
  templateUrl: "./faq.component.html",
  styleUrls: ["./faq.component.scss"]
})
export class FAQComponent implements OnInit {
  faqs = [];

  constructor(private fAQService: FAQService) {
    this.fAQService.get().subscribe((result: any) => {
      if (result.success) {
        this.faqs = result.faqs;
      }
    });
  }

  ngOnInit() {}
}
