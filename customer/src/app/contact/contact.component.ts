import { Component, OnInit } from "@angular/core";
import { ContactService } from "./contact.service";

@Component({
  selector: "app-contact",
  templateUrl: "./contact.component.html",
  styleUrls: ["./contact.component.scss"]
})
export class ContactComponent implements OnInit {
  days = "0";
  hours = "0";
  minutes = "0";
  seconds = "0";
  nextCoursecity = "";

  nextCourseTime = new Date();

  constructor(private contactService: ContactService) {
    this.contactService.getNextCourseInfo().subscribe((result: any) => {
      this.nextCourseTime = new Date(result.nextCourse.start_date);
      this.nextCoursecity = result.nextCourse.city;

      setInterval(() => this.tick(), 1000);
    });
  }

  tick() {
    const today = new Date();
    const secs = (this.nextCourseTime.getTime() - today.getTime()) / 1000;

    function z(n) {
      return (n < 10 ? "0" : "") + n;
    }

    const days = secs / 3600 / 24;
    this.days = z(Math.floor(days));
    const hours = (secs % (3600 * 24)) / 3600;
    this.hours = z(Math.floor(hours));
    const minutes = (secs % 3600) / 60;
    this.minutes = z(Math.floor(minutes));
    const seconds = secs % 60;
    this.seconds = z(Math.floor(seconds));
  }

  ngOnInit() {}
}
