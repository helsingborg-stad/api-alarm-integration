import { TransformService } from "./transform-service";

https://localhost:8443/json/wp/v2/all-disturbances
describe("TransformService", () => {
    it("should upgrade legacy API response to consolidated", () => {
        const response = {
            "big": [
                { "ID": 1 },
                { "ID": 2 },
            ],
            "small": [
                { "ID": 1 },
            ]            
        }
     expect(TransformService().map(response)).toStrictEqual({
            disturbances: {
                "big": [
                    { "ID": 1 },
                    { "ID": 2 },
                ],
                "small": [
                    { "ID": 1 },
                ]            
            },
            firedangerlevel: {
                dateTimeChanged: '',
                places: []
            }

     })
    });
    it("should default API response when invalid", () => {
        const response = {}
     
        expect(TransformService().map(response)).toStrictEqual({
            disturbances: {
                "big": [
                ],
                "small": [
                ]            
            },
            firedangerlevel: {
                dateTimeChanged: '',
                places: []
            }

     })
    });
    it("should retain API response when already consolidated", () => {
        const response = {
            disturbances: {
                "big": [
                    { "ID": 1 },
                    { "ID": 2 },
                ],
                "small": [
                    { "ID": 1 },
                ]            
            },
            firedangerlevel: {
                dateTimeChanged: '123',
                places: [{
                    place: 'test',
                    level: '1'
                }]
            }
        }
     
        expect(TransformService().map(response)).toStrictEqual(response)
    });
 
 

})