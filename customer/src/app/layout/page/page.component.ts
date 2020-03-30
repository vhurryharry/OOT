import { Component, OnInit, ViewChild } from "@angular/core";
import { ActivatedRoute, Router } from "@angular/router";
import { EntityService } from "src/app/services/entity.service";

@Component({
  selector: "app-page",
  templateUrl: "./page.component.html",
  styleUrls: ["./page.component.scss"]
})
export class PageComponent implements OnInit {
  @ViewChild("root", { static: false }) root;

  slug = "";
  content = "";

  constructor(
    private router: Router,
    private route: ActivatedRoute,
    private entityService: EntityService
  ) {
    this.route.params.subscribe(params => {
      this.slug = params.slug;

      this.entityService.entity(this.slug).subscribe((result: any) => {
        if (result && result.success) {
          this.content = result.entity.content;
        } else {
          this.router.navigateByUrl("/");
        }
      });
    });
  }

  ngOnInit() {}
}
