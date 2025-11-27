
<img width="2048" height="327" alt="minecraft_title" src="https://github.com/user-attachments/assets/84c1c5c1-04a1-466a-a0db-1528d50eb8e1" />

> fork of [PocketMine‑MP](https://github.com/pmmp/PocketMine-MP) for Minecraft: Bedrock Edition servers.

> ⚠️ **Disclaimer:** CloudPM is an **fork** and is **not** affiliated with or endorsed by the PocketMine‑MP team.

---

## 🎯 Goals

- 🚀 Provide **better performance** and deeper optimizations than upstream PocketMine‑MP  
- 🔧 Offer **fine‑grained control** over server behavior for administrators  
- 🧩 Stay **compatible** with most PocketMine‑MP plugins and configurations (where possible)  
- 🧪 Introduce **engine changes** and improvements that may lead to **API differences** in the future  

---

## ⚙️ Features

### 🧠 Server Tick Optimizer

CloudPM adds a dedicated optimization config file: `server-optimizer.yml`.

From this file you can **enable or disable ticking** for different types of entities to reduce CPU usage and overall server load:

```yaml
 ---
    server-optimizer:
      tick-entities: false
      tick-animals: false
      tick-monsters: false
      tick-drops: false
    ...
```

#### 🔍 Available Options

| Option           | Type    | Description                                                                 |
|------------------|---------|-----------------------------------------------------------------------------|
| `tick-entities`  | boolean | Global switch for entity ticking. If `false`, **no entities** will tick.   |
| `tick-animals`   | boolean | Controls ticking of **passive mobs (animals)**.                            |
| `tick-monsters`  | boolean | Controls ticking of **hostile mobs (monsters)**.                           |
| `tick-drops`     | boolean | Controls ticking of **item drops** (may affect despawn or related logic).  |

> 💡 **Hint:** Disabling some ticks can greatly improve performance on low‑end hardware,  
> but it can also change or break normal gameplay mechanics and plugin behavior.

---

## 🧭 Roadmap & API Changes

CloudPM is under active development and will continue to evolve over time.

Planned and possible future changes include:

- 💨 More **aggressive optimizations** than standard PocketMine‑MP  
- 🛠️ Additional **configuration options** for fine‑tuning performance  
- 🔄 Internal **engine refactors** to improve scalability and stability  
- ⚠️ **Potential API changes** and **breaking differences** from PocketMine‑MP as optimizations grow  

Because of this:

- Plugin developers should **test plugins directly on CloudPM**  
- You **should not assume full API compatibility** with PocketMine‑MP in future versions  

---
