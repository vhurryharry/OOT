import { Component, OnInit, Input, Output, EventEmitter } from "@angular/core";

@Component({
  selector: "app-footer",
  templateUrl: "./footer.component.html",
  styleUrls: ["./footer.component.scss"]
})
export class FooterComponent implements OnInit {
  @Input()
  public price = -1;

  @Output()
  enroll: EventEmitter<any> = new EventEmitter();

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
}
