import { Component, OnInit, ViewChild } from "@angular/core";

@Component({
    selector: "app-blank",
    templateUrl: "./blank.component.html"
})
export class BlankComponent implements OnInit {
    @ViewChild("root", { static: false }) root;

    constructor() { }

    ngOnInit() { }
}
