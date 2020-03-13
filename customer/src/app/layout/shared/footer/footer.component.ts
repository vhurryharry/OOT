import { Component, OnInit, Input, Output, EventEmitter } from "@angular/core";

declare var ml_account: any;

@Component({
  selector: "app-footer",
  templateUrl: "./footer.component.html",
  styleUrls: ["./footer.component.scss"]
})
export class FooterComponent implements OnInit {
  @Input()
  public price = -1;

  @Input()
  public status = "";

  @Input()
  public showBadge = true;

  @Output()
  enroll: EventEmitter<any> = new EventEmitter();

  private email = "";

  constructor() {}

  ngOnInit() {}

  formatPrice(price) {
    return (
      "Tuition: $ " + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    );
  }

  onEnroll() {
    this.enroll.emit();
  }

  subscribe() {
    ml_account("webforms", "1835666", "j9o3x0", "show");
  }
}
