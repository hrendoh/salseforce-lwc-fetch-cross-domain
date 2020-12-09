import { LightningElement } from 'lwc';

const columns = [
  { label: '氏名', fieldName: 'name' },
  { label: '部署', fieldName: 'department' },
];

export default class FetchExample extends LightningElement {
  employees;
  columns = columns;

  async handleFetch() {
    let endPoint = "https://peaceful-mesa-70180.herokuapp.com/";
    const response = await fetch(endPoint);
    const employees = await response.json();
    this.employees = employees;
  }
}